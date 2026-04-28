import requests
from bs4 import BeautifulSoup
import json
import csv

def scrape(url: str, selector: str) -> list[dict]:
    headers = {"User-Agent": "Mozilla/5.0"}
    resp = requests.get(url, headers=headers, timeout=10)
    resp.raise_for_status()
    soup = BeautifulSoup(resp.text, "html.parser")
    return [
        {"texto": e.get_text(strip=True), "href": e.get("href")}
        for e in soup.select(selector)
    ]

def guardar_json(datos: list[dict], archivo: str):
    with open(archivo, "w", encoding="utf-8") as f:
        json.dump(datos, f, ensure_ascii=False, indent=2)

def guardar_csv(datos: list[dict], archivo: str):
    if not datos:
        return
    with open(archivo, "w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=datos[0].keys())
        writer.writeheader()
        writer.writerows(datos)

if __name__ == "__main__":
    url = input("URL: ")
    selector = input("Selector CSS (ej: a, h2, .clase): ")
    datos = scrape(url, selector)
    print(f"Encontrados: {len(datos)} elementos")
    for d in datos[:5]:
        print(d)
    guardar_json(datos, "resultado.json")
    print("Guardado en resultado.json")
