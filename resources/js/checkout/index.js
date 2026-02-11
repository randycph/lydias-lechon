window.checkoutForm = function () {
    return {

        /* ==========================
         * STATE
         * ========================== */
        carts: window.initialCarts || [],
        coupons: [],
        method: 'pickup',
        allowMultiple: false,
        deliveryFee: 0,
        deliveryFees: [],

        /* ==========================
         * FORMATTERS
         * ========================== */
        formatMoney(value) {
            return '₱' + (parseFloat(value) || 0)
                .toLocaleString(undefined, { minimumFractionDigits: 2 })
        },

        /* ==========================
         * COMPUTED GETTERS
         * ========================== */
        get formattedSubtotal() {
            const total = this.carts.reduce((sum, item) => {
                const qty = Number(item.qty) || 1
                const base = item.is_free_product ? 0 : Number(item.price) || 0
                const paella = item.paella_price > 0
                    ? Number(item.product?.paella_price || 0)
                    : 0

                return sum + ((base + paella) * qty)
            }, 0)

            return this.formatMoney(total)
        },

        /* ==========================
         * HELPERS
         * ========================== */
        itemLineTotal(item) {
            if (item.is_free_product) return '₱0.00'

            const qty = Number(item.qty) || 1
            const base = Number(item.price) || 0
            const paella = item.paella_price > 0
                ? Number(item.product?.paella_price || 0)
                : 0

            return this.formatMoney((base + paella) * qty)
        },

        itemImage(item) {
            return item?.product?.photos?.length
                ? item.product.photos[item.product.photos.length - 1].url
                : '/images/no-image.jpg'
        },

        couponDiscountLabel(coupon) {
            if (coupon.free_shipping) return 'Shipping Discount'
            if (coupon.discount_type === 'amount') {
                return '- ' + this.formatMoney(coupon.discount)
            }
            if (coupon.discount_type === 'percent') {
                return `- ${coupon.discount}%`
            }
            return ''
        },

        /* ==========================
         * TOTAL
         * ========================== */
        computeTotal() {
            let total = this.carts.reduce((sum, item) => {
                const qty = Number(item.qty) || 1
                const base = item.is_free_product ? 0 : Number(item.price) || 0
                const paella = item.paella_price > 0
                    ? Number(item.product?.paella_price || 0)
                    : 0
                return sum + ((base + paella) * qty)
            }, 0)

            if (this.method === 'delivery' && !this.allowMultiple) {
                total += this.deliveryFee || 0
            }

            if (this.allowMultiple) {
                total += this.deliveryFees.reduce((s, d) =>
                    s + (d.fee - (d.discount || 0)), 0)
            }

            // coupon effects already reflected in deliveryFees / discounts
            return this.formatMoney(total)
        },

    }
}
