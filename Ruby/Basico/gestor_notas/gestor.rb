require 'json'
require 'time'

ARCHIVO = 'notas.json'

def cargar
  File.exist?(ARCHIVO) ? JSON.parse(File.read(ARCHIVO)) : []
end

def guardar(notas)
  File.write(ARCHIVO, JSON.pretty_generate(notas))
end

def agregar(notas, titulo, contenido)
  notas << { 'id' => notas.length + 1, 'titulo' => titulo,
             'contenido' => contenido, 'fecha' => Time.now.iso8601 }
  guardar(notas)
end

def buscar(notas, texto)
  notas.select { |n| n['titulo'].include?(texto) || n['contenido'].include?(texto) }
end

notas = cargar
puts '=== Gestor de Notas ==='

loop do
  puts "\n1. Nueva  2. Ver todas  3. Buscar  4. Eliminar  5. Salir"
  print 'Opción: '
  case gets.chomp
  when '1'
    print 'Título: ';    titulo    = gets.chomp
    print 'Contenido: '; contenido = gets.chomp
    agregar(notas, titulo, contenido)
    puts 'Nota guardada.'
  when '2'
    notas.each { |n| puts "[#{n['id']}] #{n['titulo']} (#{n['fecha'][0..9]})\n  #{n['contenido']}\n" }
  when '3'
    print 'Buscar: '
    buscar(notas, gets.chomp).each { |n| puts "[#{n['id']}] #{n['titulo']}: #{n['contenido']}" }
  when '4'
    print 'ID: '
    id = gets.chomp.to_i
    notas.reject! { |n| n['id'] == id }
    guardar(notas)
    puts 'Eliminada.'
  when '5'
    break
  end
end
