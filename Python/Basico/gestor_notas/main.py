import json
from pathlib import Path
from datetime import datetime

ARCHIVO = Path("notas.json")

def cargar() -> list[dict]:
    return json.loads(ARCHIVO.read_text(encoding='utf-8')) if ARCHIVO.exists() else []

def guardar(notas: list[dict]):
    ARCHIVO.write_text(json.dumps(notas, ensure_ascii=False, indent=2), encoding='utf-8')

def agregar(notas, titulo, contenido):
    notas.append({'id': len(notas) + 1, 'titulo': titulo,
                  'contenido': contenido, 'fecha': datetime.now().isoformat()})
    guardar(notas)

def buscar(notas, texto):
    return [n for n in notas if texto.lower() in n['titulo'].lower()
            or texto.lower() in n['contenido'].lower()]

def eliminar(notas, id):
    nueva = [n for n in notas if n['id'] != id]
    guardar(nueva)
    return nueva

def main():
    notas = cargar()
    print("=== Gestor de Notas ===")
    while True:
        print("\n1. Nueva  2. Ver todas  3. Buscar  4. Eliminar  5. Salir")
        match input("Opción: "):
            case '1':
                agregar(notas, input("Título: "), input("Contenido: "))
                print("Nota guardada.")
            case '2':
                for n in notas:
                    print(f"[{n['id']}] {n['titulo']} — {n['fecha'][:10]}\n  {n['contenido']}\n")
            case '3':
                for n in buscar(notas, input("Buscar: ")):
                    print(f"[{n['id']}] {n['titulo']}: {n['contenido']}")
            case '4':
                notas = eliminar(notas, int(input("ID: ")))
                print("Eliminada.")
            case '5':
                break

if __name__ == "__main__":
    main()
