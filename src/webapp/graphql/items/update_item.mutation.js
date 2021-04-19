import { gql } from 'graphql-request'

export const UpdateItemMutation = gql`
  mutation updateItem($id: Int!, $label: String!) {
    updateItem(category: { id: $id }, label: $label) {
      id
      label
    }
  }
`
