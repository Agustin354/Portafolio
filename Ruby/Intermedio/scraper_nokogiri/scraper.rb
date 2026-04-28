require 'nokogiri'
require 'open-uri'
require 'json'
require 'csv'

def scrape(url, selector)
  doc = Nokogiri::HTML(URI.open(url, 'User-Agent' => 'Mozilla/5.0'))
  doc.css(selector).map do |el|
    { texto: el.text.strip, href: el['href'] }
  end
end

def guardar_json(datos, archivo)
  File.write(archivo, JSON.pretty_generate(datos))
  puts "Guardado en #{archivo}"
end

def guardar_csv(datos, archivo)
  return if datos.empty?

  CSV.open(archivo, 'w', headers: datos.first.keys, write_headers: true) do |csv|
    datos.each { |row| csv << row.values }
  end
  puts "Guardado en #{archivo}"
end

print 'URL: '
url = gets.chomp
print 'Selector CSS (ej: a, h2, .clase): '
selector = gets.chomp

datos = scrape(url, selector)
puts "Encontrados: #{datos.length} elementos"
datos.first(5).each { |d| puts d }

guardar_json(datos, 'resultado.json')
guardar_csv(datos, 'resultado.csv')
