class Usuario < ApplicationRecord
  has_secure_password

  validates :nombre, presence: true, length: { minimum: 2 }
  validates :email,  presence: true, uniqueness: true,
            format: { with: URI::MailTo::EMAIL_REGEXP }
  validates :password, length: { minimum: 8 }, if: :password_digest_changed?

  enum :rol, { usuario: 0, admin: 1 }, default: :usuario

  scope :admins, -> { where(rol: :admin) }
  scope :activos, -> { where(activo: true) }
end
