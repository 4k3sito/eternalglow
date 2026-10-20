# Estado del proyecto
Actualizado: 2026-10-20

## Ahora
Rama `feat/ci-deploy`: el deploy a producción entra al pipeline (spec en `docs/specs/deploy.md`).

## Hecho recientemente
- Pipeline instalado (PR #1); `rediseno-editorial` mergeada a `main` (PR #2).
- 20 pendientes migrados de `tareas-eternalglow.md` a Notion.
- SEO: páginas en inglés de Sculptra, Botox, rellenos y relleno de labios; footer y formulario en inglés en `/en/`.

## Siguiente
- Configurar GitHub para el deploy (secrets, environment `production`, `DEPLOY_MODE=script`) y hacer el primer deploy `--theme-only`.

## Decisiones
- `seo/deploy.sh` es la única receta de deploy; CI la reutiliza.
- Push a `main` despliega solo el tema; los scripts de contenido solo se corren a mano (pisan ediciones de wp-admin).

## Bloqueos
- Cédula del doctor, fotos antes/después y datos del aviso de privacidad dependen del cliente.
