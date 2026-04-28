<?php

// Archivo de referencia — las rutas GraphQL se registran en graphql.php via lighthouse

// schema.graphql (colocar en graphql/schema.graphql):
//
// type Query {
//   productos(
//     id: ID
//     categoria: String
//     min_precio: Float
//     max_precio: Float
//     limit: Int
//   ): [Producto!]! @field(resolver: "App\\GraphQL\\Queries\\ProductoQuery")
//
//   me: User @auth
// }
//
// type Producto {
//   id: ID!
//   nombre: String!
//   precio: Float!
//   stock: Int!
//   categoria: Categoria
// }
//
// type Mutation {
//   login(email: String!, password: String!): AuthPayload
//   @field(resolver: "App\\GraphQL\\Mutations\\Login")
// }
//
// type AuthPayload {
//   token: String!
//   user: User!
// }
