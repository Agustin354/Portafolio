class Tenant < ApplicationRecord
  has_many :usuarios
  has_many :productos

  validates :nombre, :subdominio, presence: true, uniqueness: true
  validates :subdominio, format: { with: /\A[a-z0-9\-]+\z/ }

  def self.actual
    RequestStore.store[:tenant]
  end

  def self.actual=(tenant)
    RequestStore.store[:tenant] = tenant
  end
end
