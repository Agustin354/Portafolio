class Tarea < ApplicationRecord
  enum :estado, { pendiente: 0, en_progreso: 1, completada: 2 }
  enum :prioridad, { baja: 0, media: 1, alta: 2 }

  validates :titulo, presence: true
  validates :estado, presence: true

  scope :pendientes, -> { where(estado: :pendiente) }
  scope :por_prioridad, -> { order(prioridad: :desc) }
end
