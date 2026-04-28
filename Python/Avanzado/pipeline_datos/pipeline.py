import csv
import json
from pathlib import Path
from dataclasses import dataclass, field
from typing import Callable

@dataclass
class Pipeline:
    pasos: list[Callable] = field(default_factory=list)

    def paso(self, fn: Callable):
        self.pasos.append(fn)
        return fn

    def ejecutar(self, datos):
        for paso in self.pasos:
            datos = paso(datos)
            print(f"[{paso.__name__}] → {len(datos)} registros")
        return datos

pipeline = Pipeline()

@pipeline.paso
def cargar(datos):
    ruta = Path("datos.csv")
    if not ruta.exists():
        return [{"nombre": "Ana", "edad": "28", "ciudad": "Cordoba"},
                {"nombre": "Luis", "edad": "abc", "ciudad": ""},
                {"nombre": "Maria", "edad": "35", "ciudad": "Rosario"}]
    with open(ruta, encoding="utf-8") as f:
        return list(csv.DictReader(f))

@pipeline.paso
def limpiar(datos):
    limpios = []
    for row in datos:
        try:
            row["edad"] = int(row["edad"])
            if not row.get("ciudad"):
                continue
            limpios.append(row)
        except ValueError:
            pass
    return limpios

@pipeline.paso
def transformar(datos):
    for row in datos:
        row["ciudad"] = row["ciudad"].upper()
        row["mayor"] = row["edad"] >= 30
    return datos

@pipeline.paso
def guardar(datos):
    with open("resultado.json", "w", encoding="utf-8") as f:
        json.dump(datos, f, ensure_ascii=False, indent=2)
    return datos

if __name__ == "__main__":
    resultado = pipeline.ejecutar(None)
    print("Pipeline completado. Resultado:", resultado)
