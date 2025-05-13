import { createReduxStore, register, select } from '@wordpress/data';

// Définir un état initial
const initialState = {
  values:     null,
  fields:     null,
  acceptance: null,
  error:      null,
};

// Actions
const actions = {
  setValue(value) {
    return {
      type: 'SET_VALUE',
      value,
    };
  },
  setFields(fields) {
    return {
      type: 'SET_FIELDS',
      fields,
    };
  },
  setAcceptance(acceptance) {
    return {
      type: 'SET_ACCEPTANCE',
      acceptance,
    };
  },
  setError(error) {
    return {
      type: 'SET_ERROR',
      error,
    };
  },
  resetError() {
    return {
      type: 'RESET_ERROR',
    };
  },
};

// Réducteur
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_VALUE':
      return {
        ...state,
        values: action.value, 
      };
    case 'SET_FIELDS':
      return {
        ...state,
        fields: action.fields, 
      };
    case 'SET_ACCEPTANCE':
      return {
        ...state,
        acceptance: action.acceptance, 
      };
    case 'SET_ERROR':
      return {
        ...state,
        error: action.error, 
      };
    case 'RESET_ERROR':
      return {
        ...state,
        error: null, 
      };
    default:
      return state;
  }
};

// Sélecteurs
const selectors = {
  getValues(state) {
    return state.values;
  },
  getFields(state) {
    return state.fields;
  },
  getAcceptance(state) {
    return state.acceptance;
  },
  getError(state) {
    return state.error;
  }
};

// Définir le nom du store
const storeName = 'wpshop/checkout-form-store';

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