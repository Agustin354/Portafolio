Rails.application.routes.draw do
  mount ActionCable.server => '/cable'
  root 'chat#index'
  resources :salas, only: %i[index show]
end
