from celery import Celery
from celery.utils.log import get_task_logger
import time

app = Celery('portfolio', broker='redis://localhost:6379/0', backend='redis://localhost:6379/1')
app.conf.task_serializer = 'json'
app.conf.result_expires = 3600

logger = get_task_logger(__name__)

@app.task(bind=True, max_retries=3)
def enviar_email(self, destinatario: str, asunto: str, cuerpo: str):
    logger.info(f"Enviando email a {destinatario}")
    try:
        time.sleep(1)
        logger.info(f"Email enviado a {destinatario}: {asunto}")
        return {"status": "enviado", "destinatario": destinatario}
    except Exception as exc:
        raise self.retry(exc=exc, countdown=60)

@app.task
def generar_reporte(datos: list[dict]) -> dict:
    logger.info(f"Generando reporte con {len(datos)} registros")
    total = sum(d.get('monto', 0) for d in datos)
    return {"total": total, "registros": len(datos), "promedio": total / len(datos) if datos else 0}

@app.task
def limpiar_temporales(directorio: str) -> int:
    import os, glob
    archivos = glob.glob(f"{directorio}/*.tmp")
    for f in archivos:
        os.remove(f)
    logger.info(f"Eliminados {len(archivos)} archivos temporales")
    return len(archivos)

# Tareas periódicas
app.conf.beat_schedule = {
    'limpiar-cada-hora': {
        'task': 'tasks.limpiar_temporales',
        'schedule': 3600.0,
        'args': ('/tmp',),
    },
}
