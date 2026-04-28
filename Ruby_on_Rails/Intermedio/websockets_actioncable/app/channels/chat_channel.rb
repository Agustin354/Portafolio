class ChatChannel < ApplicationCable::Channel
  def subscribed
    @sala = params[:sala]
    stream_from "chat_#{@sala}"
    ActionCable.server.broadcast("chat_#{@sala}",
      tipo: 'sistema', msg: "#{current_usuario.nombre} entró al chat")
  end

  def unsubscribed
    ActionCable.server.broadcast("chat_#{@sala}",
      tipo: 'sistema', msg: "#{current_usuario.nombre} salió del chat")
  end

  def hablar(data)
    ActionCable.server.broadcast("chat_#{@sala}", {
      tipo:    'mensaje',
      de:      current_usuario.nombre,
      msg:     data['msg'],
      hora:    Time.current.strftime('%H:%M')
    })
    Mensaje.create!(usuario: current_usuario, sala: @sala, contenido: data['msg'])
  end

  def escribiendo
    ActionCable.server.broadcast("chat_#{@sala}",
      tipo: 'escribiendo', usuario: current_usuario.nombre)
  end
end
