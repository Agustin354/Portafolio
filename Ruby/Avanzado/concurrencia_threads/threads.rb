require 'thread'

# Cola de trabajo compartida (producer-consumer)
class ColaDeTrabajo
  def initialize
    @cola = Queue.new
    @mutex = Mutex.new
    @resultados = []
  end

  def encolar(item)
    @cola << item
  end

  def worker(id)
    Thread.new do
      loop do
        item = @cola.pop(true) rescue break
        resultado = procesar(item)
        @mutex.synchronize { @resultados << { worker: id, item: item, resultado: resultado } }
      end
    end
  end

  def procesar(n)
    sleep(rand(0.1..0.3))
    n * n
  end

  def ejecutar(items, num_workers: 4)
    items.each { |i| encolar(i) }
    workers = num_workers.times.map { |i| worker(i + 1) }
    workers.each(&:join)
    @resultados
  end
end

puts '=== Concurrencia con Threads ==='
cola = ColaDeTrabajo.new
items = (1..20).to_a

puts "Procesando #{items.length} items con 4 workers..."
inicio = Time.now
resultados = cola.ejecutar(items, num_workers: 4)
fin = Time.now

resultados.sort_by { |r| r[:item] }.each do |r|
  puts "Worker #{r[:worker]}: #{r[:item]}^2 = #{r[:resultado]}"
end
puts "Tiempo total: #{(fin - inicio).round(2)}s"
