require 'faye'
require 'rack'

# Middleware de autenticación para canales privados
class AuthExtension
  def incoming(message, callback)
    if message['channel'] =~ %r{^/privado/}
      token = message.dig('ext', 'token')
      message['error'] = 'No autorizado' unless token == 'token_secreto'
    end
    callback.call(message)
  end
end

bayeux = Faye::RackAdapter.new(mount: '/faye', timeout: 25)
bayeux.add_extension(AuthExtension.new)

Faye::WebSocket.load_adapter('thin')

puts 'Servidor Faye WebSocket en http://localhost:9292/faye'
run bayeux
