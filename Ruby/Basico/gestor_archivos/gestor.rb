require 'fileutils'

def listar(dir)
  Dir.entries(dir).reject { |f| f.start_with?('.') }.each { |f| puts f }
end

def crear_archivo(ruta, contenido = '')
  File.write(ruta, contenido)
  puts "Archivo creado: #{ruta}"
end

def copiar(origen, destino)
  FileUtils.cp(origen, destino)
  puts "Copiado: #{origen} → #{destino}"
end

def mover(origen, destino)
  FileUtils.mv(origen, destino)
  puts "Movido: #{origen} → #{destino}"
end

def eliminar(ruta)
  File.delete(ruta)
  puts "Eliminado: #{ruta}"
rescue Errno::ENOENT
  puts "Archivo no encontrado: #{ruta}"
end

def buscar(dir, patron)
  Dir.glob("#{dir}/**/#{patron}").each { |f| puts f }
end

puts '=== Gestor de Archivos ==='
loop do
  puts "\n1. Listar  2. Crear  3. Copiar  4. Mover  5. Eliminar  6. Buscar  7. Salir"
  print 'Opción: '
  case gets.chomp
  when '1'
    print 'Directorio: '; listar(gets.chomp)
  when '2'
    print 'Ruta: '; ruta = gets.chomp
    print 'Contenido: '; crear_archivo(ruta, gets.chomp)
  when '3'
    print 'Origen: '; o = gets.chomp; print 'Destino: '; copiar(o, gets.chomp)
  when '4'
    print 'Origen: '; o = gets.chomp; print 'Destino: '; mover(o, gets.chomp)
  when '5'
    print 'Ruta: '; eliminar(gets.chomp)
  when '6'
    print 'Directorio: '; d = gets.chomp; print 'Patrón (ej: *.rb): '; buscar(d, gets.chomp)
  when '7'
    break
  end
end
