class Proyecto < ApplicationRecord
  has_and_belongs_to_many :tecnologias
  has_one_attached :imagen

  validates :titulo, presence: true, length: { minimum: 3 }
  validates :descripcion, presence: true

  scope :publicados, -> { where(publicado: true) }
end
