import Product from "./Product";

export default interface Order {
    id: number;
    name: string;
    description: string;
    date: string;
    products?: Product[]
}