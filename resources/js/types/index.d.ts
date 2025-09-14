import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';
import type { AxiosInstance } from 'axios'
import type { route as routeFn } from 'ziggy-js'
import type { User } from '@/types/user'

declare global {
    interface Window {
        axios: AxiosInstance
    }

    // eslint-disable-next-line no-var
    var route: typeof routeFn
}

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

/** export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
} **/

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    cart: {
        count: number;
    }
};

export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    state: string | null;
    postal_code: string | null;
    city: string | null;
    street_address: string | null;
    building: string | null;
    phone_number: string | null;
    avatar?: string; 
};

export type Product = {
    id: number;
    name: string;
    description: string;
    price: number;
    image: string;
    stock: number;
    created_at: string;
    updated_at: string;
};
