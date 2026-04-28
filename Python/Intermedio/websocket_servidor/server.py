import asyncio
import json
import websockets

clientes: set = set()

async def broadcast(mensaje: str, emisor=None):
    destinatarios = clientes - {emisor}
    if destinatarios:
        await asyncio.gather(*[c.send(mensaje) for c in destinatarios])

async def handler(websocket):
    clientes.add(websocket)
    nombre = f"Usuario_{len(clientes)}"
    print(f"[+] {nombre} conectado. Total: {len(clientes)}")

    await broadcast(json.dumps({"tipo": "sistema", "msg": f"{nombre} entró al chat"}), websocket)

    try:
        async for mensaje in websocket:
            datos = json.loads(mensaje)
            payload = json.dumps({"tipo": "mensaje", "de": nombre, "msg": datos.get("msg", "")})
            print(f"{nombre}: {datos.get('msg')}")
            await broadcast(payload, websocket)
    except websockets.exceptions.ConnectionClosed:
        pass
    finally:
        clientes.discard(websocket)
        await broadcast(json.dumps({"tipo": "sistema", "msg": f"{nombre} salió del chat"}))
        print(f"[-] {nombre} desconectado. Total: {len(clientes)}")

async def main():
    print("Servidor WebSocket en ws://localhost:8765")
    async with websockets.serve(handler, "localhost", 8765):
        await asyncio.Future()

if __name__ == "__main__":
    asyncio.run(main())
