def calcular(a, op, b)
  case op
  when '+' then a + b
  when '-' then a - b
  when '*' then a * b
  when '/' then b != 0 ? a / b.to_f : 'Error: división por cero'
  else 'Operador no válido'
  end
end

puts '=== Calculadora CLI ==='
loop do
  print 'Operación (ej: 5 + 3) o "salir": '
  entrada = gets.chomp
  break if entrada == 'salir'

  partes = entrada.split
  if partes.length == 3
    a, op, b = partes[0].to_f, partes[1], partes[2].to_f
    puts "Resultado: #{calcular(a, op, b)}"
  else
    puts 'Formato inválido. Usá: número operador número'
  end
end
