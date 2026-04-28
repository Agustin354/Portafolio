import redis
import json
import time
from functools import wraps
from typing import Callable, Any

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

def cache(ttl: int = 60, prefix: str = "cache"):
    def decorator(fn: Callable) -> Callable:
        @wraps(fn)
        def wrapper(*args, **kwargs):
            clave = f"{prefix}:{fn.__name__}:{args}:{kwargs}"
            cached = r.get(clave)
            if cached:
                print(f"[CACHE HIT] {clave}")
                return json.loads(cached)
            print(f"[CACHE MISS] {clave}")
            resultado = fn(*args, **kwargs)
            r.setex(clave, ttl, json.dumps(resultado))
            return resultado
        return wrapper
    return decorator

def invalidar(patron: str):
    claves = r.keys(patron)
    if claves:
        r.delete(*claves)
    return len(claves)

@cache(ttl=30, prefix="productos")
def obtener_producto(id: int) -> dict:
    time.sleep(0.5)
    return {"id": id, "nombre": f"Producto {id}", "precio": id * 10.5}

@cache(ttl=60, prefix="reportes")
def generar_reporte(mes: int, anio: int) -> dict:
    time.sleep(1.0)
    return {"mes": mes, "anio": anio, "total": mes * anio * 100}

if __name__ == "__main__":
    print("Primera llamada (sin cache):")
    print(obtener_producto(1))

    print("\nSegunda llamada (con cache):")
    print(obtener_producto(1))

    print("\nReporte (sin cache):")
    print(generar_reporte(4, 2026))

    print("\nReporte (con cache):")
    print(generar_reporte(4, 2026))

    eliminadas = invalidar("productos:*")
    print(f"\nCache invalidado: {eliminadas} claves eliminadas")
