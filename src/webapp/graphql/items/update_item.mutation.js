import { gql } from 'graphql-request'

export const UpdateItemMutation = gql`
  mutation updateItem($id: Int!, $label: String!) {
    updateItem(item: { id: $id }, label: $label) {
      id
      label
    }
  }
`
