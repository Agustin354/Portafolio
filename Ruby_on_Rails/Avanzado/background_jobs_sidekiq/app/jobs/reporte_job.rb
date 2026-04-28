class ReporteJob < ApplicationJob
  queue_as :reportes
  sidekiq_options retry: 2, dead: false

  def perform(usuario_id, mes, anio)
    usuario = Usuario.find(usuario_id)
    datos   = Venta.where(mes: mes, anio: anio).includes(:producto)
    reporte = GeneradorReporte.new(datos).generar

    ReporteMailer.mensual(usuario, reporte).deliver_now
    Rails.logger.info "Reporte #{mes}/#{anio} enviado a #{usuario.email}"
  rescue ActiveRecord::RecordNotFound => e
    Rails.logger.error "Usuario #{usuario_id} no encontrado: #{e.message}"
  end
end
