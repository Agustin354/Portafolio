require 'sidekiq/web'

Rails.application.routes.draw do
  authenticate :usuario, ->(u) { u.admin? } do
    mount Sidekiq::Web => '/sidekiq'
  end

  resources :reportes, only: %i[index create show]
end
