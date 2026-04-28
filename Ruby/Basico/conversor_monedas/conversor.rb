TASAS = { 'USD' => 1.0, 'ARS' => 900.0, 'EUR' => 0.92, 'BRL' => 5.0, 'CLP' => 950.0, 'MXN' => 17.5 }.freeze

def convertir(monto, origen, destino)
  en_usd = monto / TASAS[origen.upcase]
  en_usd * TASAS[destino.upcase]
end

puts "=== Conversor de Monedas ==="
puts "Monedas: #{TASAS.keys.join(', ')}"

loop do
  print "\nConvertir (ej: 100 ARS USD) o 'salir': "
  entrada = gets.chomp
  break if entrada == 'salir'

  partes = entrada.split
  if partes.length == 3
    monto, origen, destino = partes[0].to_f, partes[1], partes[2]
    resultado = convertir(monto, origen, destino)
    puts "#{monto} #{origen.upcase} = #{'%.2f' % resultado} #{destino.upcase}"
  else
    puts "Formato inválido o moneda no soportada."
  end
rescue KeyError
  puts "Moneda no soportada."
end
