import { createReduxStore, register, select } from '@wordpress/data';

// Définir un état initial
const initialState = {
  page: 'orders',
};

// Actions
const actions = {
  setPage(value) {
    return {
      type: 'SET_PAGE',
      value,
    };
  }
};

// Réducteur
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_PAGE':
      return {
        ...state,
        page: action.value,
      };
    default:
      return state;
  }
};

// Sélecteurs
const selectors = {
  getPage(state) {
    return state.page;
  }
};

// Définir le nom du store
const storeName = 'wpshop/my-account-store';

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