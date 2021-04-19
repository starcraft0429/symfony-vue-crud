import { gql } from 'graphql-request'

export const CreateItemMutation = gql`
  mutation createItem($label: String!) {
    createItem(label: $label) {
      id
      label
    }
  }
`
