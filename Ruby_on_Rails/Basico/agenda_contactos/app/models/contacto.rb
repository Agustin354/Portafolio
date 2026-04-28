class Contacto < ApplicationRecord
  validates :nombre, presence: true
  validates :email, format: { with: URI::MailTo::EMAIL_REGEXP }, allow_blank: true
  validates :telefono, format: { with: /\A[\d\s\-+()]+\z/ }, allow_blank: true

  scope :buscar, ->(q) { where('nombre LIKE ? OR email LIKE ?', "%#{q}%", "%#{q}%") }
  scope :por_letra, ->(l) { where('nombre LIKE ?', "#{l}%").order(:nombre) }
end
