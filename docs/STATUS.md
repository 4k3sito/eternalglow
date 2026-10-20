# Estado del proyecto
Actualizado: 2026-10-20

## Ahora
Deploy a producción integrado al pipeline y probado (spec en `docs/specs/deploy.md`).

## Hecho recientemente
- Deploy por GitHub Actions (PR #3); primer deploy `--theme-only` OK el 2026-10-20, run 39391632264.
- Pipeline instalado (PR #1); `rediseno-editorial` mergeada a `main` (PR #2).
- 20 pendientes migrados de `tareas-eternalglow.md` a Notion.
- SEO: páginas en inglés de Sculptra, Botox, rellenos y relleno de labios; footer y formulario en inglés en `/en/`.

## Siguiente
- Primer deploy con `run_content_scripts` cuando haya un cambio de contenido.
- Atacar los pendientes de Notion (prioridad Alta: formulario, enlaces internos a hubs, indexación /en/).

## Decisiones
- `seo/deploy.sh` es la única receta de deploy; CI la reutiliza.
- Push a `main` despliega solo el tema; los scripts de contenido solo se corren a mano (pisan ediciones de wp-admin).

## Bloqueos
- Cédula del doctor, fotos antes/después y datos del aviso de privacidad dependen del cliente.
