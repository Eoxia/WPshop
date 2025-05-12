import { createReduxStore, register, select } from '@wordpress/data';

// Définir un état initial
const initialState = {
  products: null
};

// Actions
const actions = {
  setValue(value) {
    return {
      type: 'SET_VALUE',
      value,
    };
  },
};

// Réducteur
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_VALUE':
      return {
        ...state,
        products: action.value, 
      };
    default:
      return state;
  }
};

// Sélecteurs
const selectors = {
  getProducts(state) {
    return state.products;
  },
};

// Définir le nom du store
const storeName = 'wpshop/cart-products-store';

if (!select(storeName)) {
  let store = createReduxStore(storeName, {
    reducer,
    actions,
    selectors,
  });

  // Enregistrer le store
  register(store);
}

export { storeName };