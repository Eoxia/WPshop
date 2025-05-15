/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/src/store/cart-products-store.js":
/*!*************************************************!*\
  !*** ./blocks/src/store/cart-products-store.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   storeName: () => (/* binding */ storeName)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);


// Définir un état initial
const initialState = {
  products: null
};

// Actions
const actions = {
  setValue(value) {
    return {
      type: 'SET_VALUE',
      value
    };
  }
};

// Réducteur
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_VALUE':
      return {
        ...state,
        products: action.value
      };
    default:
      return state;
  }
};

// Sélecteurs
const selectors = {
  getProducts(state) {
    return state.products;
  }
};

// Définir le nom du store
const storeName = 'wpshop/cart-products-store';
if (!(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(storeName)) {
  let store = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.createReduxStore)(storeName, {
    reducer,
    actions,
    selectors
  });

  // Enregistrer le store
  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.register)(store);
}


/***/ }),

/***/ "./blocks/src/utils/index.js":
/*!***********************************!*\
  !*** ./blocks/src/utils/index.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _render__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _render__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./render */ "./blocks/src/utils/render.js");


/***/ }),

/***/ "./blocks/src/utils/render.js":
/*!************************************!*\
  !*** ./blocks/src/utils/render.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function render(element, containerClass) {
  document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector(containerClass);
    if (!container) {
      return;
    }
    const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(container);
    root.render(element);
  });
}

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!******************************************************************!*\
  !*** ./blocks/src/cart-block/inner-blocks/cart-products/view.js ***!
  \******************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../utils */ "./blocks/src/utils/index.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _store_cart_products_store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../store/cart-products-store */ "./blocks/src/store/cart-products-store.js");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);







const CartProductsList = () => {
  // Récupérer la valeur du compteur depuis le store
  const products = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    try {
      const store = select(_store_cart_products_store__WEBPACK_IMPORTED_MODULE_4__.storeName);
      return store ? store.getProducts() : 0;
    } catch (e) {
      console.error('Erreur lors de l\'accès au store:', e);
      return 0;
    }
  }, []);
  const {
    setValue
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useDispatch)(_store_cart_products_store__WEBPACK_IMPORTED_MODULE_4__.storeName);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: '/wp-shop/v1/cart/'
    }).then(response => {
      if (response) {
        setValue(response);
      } else {
        console.error('Erreur lors de la récupération des produits:', response);
      }
    });
  }, []);
  const suppressProduct = productId => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: `/wp-shop/v1/cart/${productId}`,
      method: 'DELETE'
    }).then(response => {
      if (response) {
        if (!response?.products?.length) {
          window.location.reload();
        } else {
          setValue(response);
        }
      } else {
        console.error('Erreur lors de la suppression du produit:', response);
      }
    });
  };
  const incrementProduct = productId => {
    // Optimistic update - Create a copy of the current state
    const previousState = JSON.parse(JSON.stringify(products));

    // Update the product quantity locally first
    const updatedProducts = {
      ...products,
      products: products.products.map(product => {
        if (product.id === productId) {
          return {
            ...product,
            qty: product.qty + 1
          };
        }
        return product;
      })
    };
    updatedProducts.total = updatedProducts.products.reduce((acc, product) => {
      return acc + product.price * product.qty;
    }, 0);
    updatedProducts.total_ttc = updatedProducts.products.reduce((acc, product) => {
      return acc + product.price_ttc * product.qty;
    }, 0);
    updatedProducts.taxes = updatedProducts.total_ttc - updatedProducts.total;

    // Update UI immediately
    setValue(updatedProducts);

    // Then make the API call
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: `/wp-shop/v1/cart/${productId}/increment`,
      method: 'POST',
      data: {
        qty: 1
      }
    }).then(response => {
      if (!response) {
        console.error('Erreur lors de l\'incrémentation du produit:', response);
        setValue(previousState);
      }
    }).catch(error => {
      console.error('Échec de l\'incrémentation du produit:', error);
      // Restore previous state if request fails
      setValue(previousState);
    });
  };
  const decrementProduct = productId => {
    // Find the current product
    const currentProduct = products.products.find(product => product.id === productId);
    if (!currentProduct) return;

    // Optimistic update - Create a copy of the current state
    const previousState = JSON.parse(JSON.stringify(products));

    // If qty will be 0, handle differently (potential removal)
    if (currentProduct.qty <= 1) {
      // For qty=1, we'll let the API handle it as normal (may need to refresh)
      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
        path: `/wp-shop/v1/cart/${productId}/decrement`,
        method: 'POST'
      }).then(response => {
        if (response) {
          if (!response?.products?.length) {
            window.location.reload();
          } else {
            setValue(response);
          }
        } else {
          console.error('Erreur lors de la décrémentation du produit:', response);
        }
      }).catch(error => {
        console.error('Échec de la décrémentation du produit:', error);
      });
      return;
    }

    // Update the product quantity locally first
    const updatedProducts = {
      ...products,
      products: products.products.map(product => {
        if (product.id === productId) {
          return {
            ...product,
            qty: product.qty - 1
          };
        }
        return product;
      })
    };
    updatedProducts.total = updatedProducts.products.reduce((acc, product) => {
      return acc + product.price * product.qty;
    }, 0);
    updatedProducts.total_ttc = updatedProducts.products.reduce((acc, product) => {
      return acc + product.price_ttc * product.qty;
    }, 0);
    updatedProducts.taxes = updatedProducts.total_ttc - updatedProducts.total;

    // Update UI immediately
    setValue(updatedProducts);

    // Then make the API call
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: `/wp-shop/v1/cart/${productId}/decrement`,
      method: 'POST'
    }).then(response => {
      if (!response) {
        console.error('Erreur lors de la décrémentation du produit:', response);
        // Restore previous state if request fails
        setValue(previousState);
      }
    }).catch(error => {
      console.error('Échec de la décrémentation du produit:', error);
      // Restore previous state if request fails
      setValue(previousState);
    });
  };
  if (!products) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "loading",
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Chargement des produits...', 'wpshop')
    });
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
    className: "wps-list-product gridw-2",
    children: products.products?.map(product => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
      itemscope: true,
      itemtype: "https://schema.org/Product",
      class: "wps-product",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("a", {
        class: "wps-delete-product",
        onClick: () => suppressProduct(product.id),
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("i", {
          class: "wps-delete-product-icon fas fa-times-circle"
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("figure", {
        class: "wps-product-thumbnail",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("img", {
          src: product.thumbnail_url,
          class: "attachment-wps-product-thumbnail",
          itemprop: "image"
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
        class: "wps-product-content",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          itemprop: "name",
          class: "wps-product-title",
          children: product.title
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("ul", {
          class: "wps-product-attributes",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("li", {
            class: "wps-product-attributes-item",
            children: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Unit price:', 'wpshop'), " ", product.price_ttc, " \u20AC"]
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
          class: "wps-product-footer",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            class: "wps-product-quantity",
            style: {
              fontSize: '1.4em'
            },
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
              class: "wps-quantity-minus fas fa-minus-circle",
              onClick: () => decrementProduct(product.id)
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
              class: "qty",
              children: product.qty
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
              class: "wps-quantity-plus fas fa-plus-circle",
              onClick: () => incrementProduct(product.id)
            })]
          }), product.price_ttc && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
            itemprop: "offers",
            itemscope: true,
            itemtype: "https://schema.org/Offer",
            class: "wps-product-price",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
              itemprop: "price",
              children: (product.price_ttc * product.qty).toFixed(2)
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("span", {
              itemprop: "priceCurrency",
              content: "EUR",
              children: "\u20AC"
            })]
          })]
        })]
      })]
    }))
  });
};
(0,_utils__WEBPACK_IMPORTED_MODULE_0__.render)(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(CartProductsList, {}), '.wp-block-wpshop-cart-products');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CartProductsList);
})();

/******/ })()
;
//# sourceMappingURL=view.js.map