<div id='product-component-1677355642734'></div>
<script type="text/javascript">
/*<![CDATA[*/
(function () {
  var scriptURL = 'https://sdks.shopifycdn.com/buy-button/latest/buy-button-storefront.min.js';
  if (window.ShopifyBuy) {
    if (window.ShopifyBuy.UI) {
      ShopifyBuyInit();
    } else {
      loadScript();
    }
  } else {
    loadScript();
  }
  function loadScript() {
    var script = document.createElement('script');
    script.async = true;
    script.src = scriptURL;
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
    script.onload = ShopifyBuyInit;
  }
  function ShopifyBuyInit() {
    var client = ShopifyBuy.buildClient({
      domain: 'gaywellness.myshopify.com',
      storefrontAccessToken: '38319c7f6e4ffa3cd9a378b6908a2f73',
    });
    ShopifyBuy.UI.onReady(client).then(function (ui) {
      ui.createComponent('product', {
        id: '8084955824442',
        node: document.getElementById('product-component-1677355642734'),
        moneyFormat: '%24%7B%7Bamount%7D%7D',
        options: {
  "product": {
    "styles": {
      "product": {
        "@media (min-width: 601px)": {
          "max-width": "calc(25% - 20px)",
          "margin-left": "20px",
          "margin-bottom": "50px"
        }
      },
      "button": {
        "font-size": "16px",
        "padding-top": "16px",
        "padding-bottom": "16px",
        "color": "#fef4e8",
        ":hover": {
          "color": "#fef4e8",
          "background-color": "#e15327"
        },
        "background-color": "#fa5c2b",
        ":focus": {
          "background-color": "#e15327"
        },
        "border-radius": "12px"
      },
      "quantityInput": {
        "font-size": "16px",
        "padding-top": "16px",
        "padding-bottom": "16px"
      }
    },
    "text": {
      "button": "Add to cart"
    }
  },
  "productSet": {
    "styles": {
      "products": {
        "@media (min-width: 601px)": {
          "margin-left": "-20px"
        }
      }
    }
  },
  "modalProduct": {
    "contents": {
      "img": false,
      "imgWithCarousel": true,
      "button": false,
      "buttonWithQuantity": true
    },
    "styles": {
      "product": {
        "@media (min-width: 601px)": {
          "max-width": "100%",
          "margin-left": "0px",
          "margin-bottom": "0px"
        }
      },
      "button": {
        "font-size": "16px",
        "padding-top": "16px",
        "padding-bottom": "16px",
        "color": "#fef4e8",
        ":hover": {
          "color": "#fef4e8",
          "background-color": "#e15327"
        },
        "background-color": "#fa5c2b",
        ":focus": {
          "background-color": "#e15327"
        },
        "border-radius": "12px"
      },
      "quantityInput": {
        "font-size": "16px",
        "padding-top": "16px",
        "padding-bottom": "16px"
      }
    },
    "text": {
      "button": "Add to cart"
    }
  },
  "option": {},
  "cart": {
    "styles": {
      "button": {
        "font-size": "16px",
        "padding-top": "16px",
        "padding-bottom": "16px",
        "color": "#fef4e8",
        ":hover": {
          "color": "#fef4e8",
          "background-color": "#e15327"
        },
        "background-color": "#fa5c2b",
        ":focus": {
          "background-color": "#e15327"
        },
        "border-radius": "12px"
      }
    },
    "text": {
      "total": "Subtotal",
      "button": "Checkout"
    }
  },
  "toggle": {
    "styles": {
      "toggle": {
        "background-color": "#fa5c2b",
        ":hover": {
          "background-color": "#e15327"
        },
        ":focus": {
          "background-color": "#e15327"
        }
      },
      "count": {
        "font-size": "16px",
        "color": "#fef4e8",
        ":hover": {
          "color": "#fef4e8"
        }
      },
      "iconPath": {
        "fill": "#fef4e8"
      }
    }
  }
},
      });
    });
  }
})();
/*]]>*/
</script>