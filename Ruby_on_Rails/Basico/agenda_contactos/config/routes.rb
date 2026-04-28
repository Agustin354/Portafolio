Rails.application.routes.draw do
  root 'contactos#index'
  resources :contactos
end
