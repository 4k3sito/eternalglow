"""OAuth compartido para las APIs de Google (GBP, GTM, GA4, Search Console).

Un solo token en seo/.secrets/google-token.json con todos los scopes, para autorizar una sola vez.
Sin scope de publicación de GTM a propósito: los cambios quedan en borrador hasta que una persona publique.
"""
from __future__ import annotations

import os
import random
import sys
import time
from pathlib import Path
from typing import Any

import requests
from google.auth.transport.requests import Request
from google.oauth2.credentials import Credentials
from google_auth_oauthlib.flow import InstalledAppFlow

SECRETS_DIR = Path(__file__).resolve().parent / ".secrets"
TOKEN_FILE = SECRETS_DIR / "google-token.json"
# Puerto fijo: el cliente OAuth tipo "web" tiene registrada exactamente http://localhost:8765/
OAUTH_PORT = 8765

SCOPES = [
    "https://www.googleapis.com/auth/business.manage",
    "https://www.googleapis.com/auth/tagmanager.edit.containers",
    "https://www.googleapis.com/auth/tagmanager.edit.containerversions",
    "https://www.googleapis.com/auth/analytics.edit",
    "https://www.googleapis.com/auth/analytics.readonly",
    "https://www.googleapis.com/auth/webmasters.readonly",
]


class GoogleApiError(RuntimeError):
    pass


def _client_secret() -> Path:
    if env := os.environ.get("GOOGLE_CLIENT_SECRET"):
        return Path(env)
    candidates = [p for p in SECRETS_DIR.glob("*.json") if not p.name.endswith("-token.json")]
    if len(candidates) != 1:
        raise GoogleApiError(f"Se esperaba 1 JSON de cliente OAuth en {SECRETS_DIR}, hay {len(candidates)}. Usa GOOGLE_CLIENT_SECRET.")
    return candidates[0]


def get_credentials() -> Credentials:
    creds: Credentials | None = None
    if TOKEN_FILE.exists():
        creds = Credentials.from_authorized_user_file(str(TOKEN_FILE))
        # Un token viejo con menos scopes obliga a re-autorizar en vez de fallar con 403 a mitad del script.
        if not set(SCOPES) <= set(creds.scopes or []):
            creds = None
    if creds and creds.expired and creds.refresh_token:
        try:
            creds.refresh(Request())
        except Exception as exc:  # revocado, o caducado por modo Testing (7 días)
            print(f"No se pudo refrescar el token ({exc}); se pide autorización de nuevo.", file=sys.stderr)
            creds = None
    if not creds or not creds.valid:
        flow = InstalledAppFlow.from_client_secrets_file(str(_client_secret()), SCOPES)
        creds = flow.run_local_server(
            port=OAUTH_PORT,
            open_browser=False,  # en WSL no hay navegador; el enlace se abre a mano en Windows
            access_type="offline",
            prompt="consent",
            authorization_prompt_message="Abre este enlace y entra con la cuenta dueña de GTM/GA4/Search Console:\n{url}\n",
        )
    TOKEN_FILE.write_text(creds.to_json())
    TOKEN_FILE.chmod(0o600)
    return creds


class GoogleClient:
    def __init__(self, creds: Credentials) -> None:
        self._session = requests.Session()
        self._session.headers["Authorization"] = f"Bearer {creds.token}"

    def request(self, method: str, url: str, **kwargs: Any) -> dict[str, Any]:
        # GTM limita a ~0.25 req/s por usuario: 429/5xx se reintentan con backoff exponencial y jitter.
        for attempt in range(6):
            resp = self._session.request(method, url, timeout=30, **kwargs)
            if resp.status_code not in (429, 500, 502, 503) or attempt == 5:
                break
            time.sleep(2 ** attempt + random.random())
        if not resp.ok:
            raise GoogleApiError(f"{resp.status_code} {method} {url}\n{resp.text[:800]}")
        return resp.json() if resp.content else {}

    def get(self, url: str, params: Any = None) -> dict[str, Any]:
        return self.request("GET", url, params=params)

    def post(self, url: str, body: dict[str, Any] | None = None) -> dict[str, Any]:
        return self.request("POST", url, json=body)

    def put(self, url: str, body: dict[str, Any]) -> dict[str, Any]:
        return self.request("PUT", url, json=body)

    def paginate(self, url: str, key: str, params: dict[str, Any] | None = None) -> list[dict[str, Any]]:
        params, items = dict(params or {}), []
        while True:
            data = self.get(url, params)
            items += data.get(key, [])
            if not (token := data.get("nextPageToken")):
                return items
            params["pageToken"] = token
