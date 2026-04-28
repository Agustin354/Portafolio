module MiGem
  VERSION = '0.1.0'

  class Formateador
    COLORES = { rojo: 31, verde: 32, amarillo: 33, azul: 34 }.freeze

    def self.colorear(texto, color)
      codigo = COLORES.fetch(color, 0)
      "\e[#{codigo}m#{texto}\e[0m"
    end

    def self.tabla(filas, encabezados: nil)
      columnas = encabezados ? [encabezados] + filas : filas
      anchos = columnas.first.length.times.map do |i|
        columnas.map { |f| f[i].to_s.length }.max
      end
      separador = anchos.map { |a| '-' * (a + 2) }.join('+')
      linea = lambda { |fila| fila.map.with_index { |c, i| " #{c.to_s.ljust(anchos[i])} " }.join('|') }

      if encabezados
        puts linea.call(encabezados)
        puts separador
      end
      filas.each { |f| puts linea.call(f) }
    end
  end

  class Validador
    def self.email?(str)
      str.match?(/\A[\w+\-.]+@[a-z\d\-.]+\.[a-z]+\z/i)
    end

    def self.url?(str)
      str.match?(%r{\Ahttps?://\S+\z})
    end

    def self.solo_numeros?(str)
      str.match?(/\A\d+\z/)
    end
  end
end
