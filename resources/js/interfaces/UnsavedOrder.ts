import Order from "./Order";
import Product from "./Product";

// eslint-disable-next-line @typescript-eslint/no-empty-object-type
export interface UnsavedOrder extends Omit<Order, 'id' | 'date'> {
    products: Product[]
}