require 'sidekiq'

Sidekiq.configure_client { |c| c.redis = { url: 'redis://localhost:6379/0' } }
Sidekiq.configure_server { |c| c.redis = { url: 'redis://localhost:6379/0' } }

class EmailWorker
  include Sidekiq::Worker
  sidekiq_options queue: :emails, retry: 3

  def perform(destinatario, asunto, cuerpo)
    sleep(0.5)
    puts "[EmailWorker] Enviado a #{destinatario}: #{asunto}"
  end
end

class ReporteWorker
  include Sidekiq::Worker
  sidekiq_options queue: :reportes, retry: 1

  def perform(mes, anio)
    puts "[ReporteWorker] Generando reporte #{mes}/#{anio}..."
    sleep(1)
    puts "[ReporteWorker] Reporte completado"
  end
end

class LimpiezaWorker
  include Sidekiq::Worker
  sidekiq_options queue: :mantenimiento, retry: false

  def perform(directorio)
    require 'fileutils'
    archivos = Dir.glob("#{directorio}/*.tmp")
    archivos.each { |f| File.delete(f) }
    puts "[LimpiezaWorker] Eliminados #{archivos.count} temporales en #{directorio}"
  end
end
