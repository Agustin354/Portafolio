class Post < ApplicationRecord
  validates :titulo, presence: true, length: { minimum: 3, maximum: 100 }
  validates :contenido, presence: true, length: { minimum: 10 }

  scope :recientes, -> { order(created_at: :desc) }
  scope :publicados, -> { where(publicado: true) }
end
