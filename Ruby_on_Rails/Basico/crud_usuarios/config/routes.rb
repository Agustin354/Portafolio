Rails.application.routes.draw do
  root 'usuarios#index'
  resources :usuarios
end
