import { UnsavedOrder } from '../interfaces/UnsavedOrder';
import axiosInstance from './axios-instance';

const API_BASE = '/api/v1/orders';

export const getOrders = (queryParams: { name?: string, description?: string, date?: string }) => axiosInstance.get(API_BASE, {
    params: queryParams,
});

export const getOrder = (id: number) => axiosInstance.get(`${API_BASE}/${id}`);

export const createOrder = (order: UnsavedOrder) => axiosInstance.post(API_BASE, order);

export const updateOrder = (id: number, params: { name?: string, description?: string }) => axiosInstance.put(`${API_BASE}/${id}`, params);

export const deleteOrder = (id: number) => axiosInstance.delete(`${API_BASE}/${id}`);

export const removeProductOrder = (orderId: number, productId: number) => axiosInstance.delete(`${API_BASE}/${orderId}/products/${productId}`);

export const addProductOrder = (orderId: number, productId: number) => axiosInstance.post(`${API_BASE}/${orderId}/products/${productId}`);