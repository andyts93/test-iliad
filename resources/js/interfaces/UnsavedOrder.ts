import Order from "./Order";
import Product from "./Product";

export interface UnsavedOrder extends Omit<Order, 'id' | 'date'> {
    products: Product[]
}