PALABRAS = %w[ruby programacion algoritmo variable metodo clase modulo iterador bloque simbolo]

DIBUJOS = [
  "  -----\n  |   |\n      |\n      |\n      |\n=========",
  "  -----\n  |   |\n  O   |\n      |\n      |\n=========",
  "  -----\n  |   |\n  O   |\n  |   |\n      |\n=========",
  "  -----\n  |   |\n  O   |\n /|   |\n      |\n=========",
  "  -----\n  |   |\n  O   |\n /|\\  |\n      |\n=========",
  "  -----\n  |   |\n  O   |\n /|\\  |\n /    |\n=========",
  "  -----\n  |   |\n  O   |\n /|\\  |\n / \\  |\n========="
]

def jugar
  palabra     = PALABRAS.sample
  adivinadas  = []
  intentos    = 0
  max         = DIBUJOS.length - 1

  loop do
    visible = palabra.chars.map { |l| adivinadas.include?(l) ? l : '_' }.join(' ')
    puts "\n#{DIBUJOS[intentos]}\n#{visible}\nIntentados: #{adivinadas.sort.join(', ')}"

    return puts('¡Ganaste!') unless visible.include?('_')
    return puts("¡Perdiste! La palabra era: #{palabra}") if intentos >= max

    print 'Letra: '
    letra = gets.chomp.downcase
    next puts('Ingresá una sola letra.') unless letra.length == 1 && letra.match?(/[a-z]/)
    next puts('Ya la intentaste.') if adivinadas.include?(letra)

    adivinadas << letra
    intentos += 1 unless palabra.include?(letra)
  end
end

puts '=== Ahorcado ==='
loop do
  jugar
  print '¿Jugar de nuevo? (s/n): '
  break unless gets.chomp.downcase == 's'
end
