export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | "";
    password: string;
    remember_token: string;
    created_at: string;
    updated_at: string;
    role: 'admin' | 'chef' | 'staff' | 'customer';
    imgPath: string | "";
    firstname: string;
    surname: string;
    age: number;
};

export type PasswordResetToken = {
    email: string;
    token: string;
    created_at: string | "";
};

export type FailedJob = {
    id: number;
    uuid: string;
    connection: string;
    queue: string;
    payload: string;
    exception: string;
    failed_at: string;
};

export type PersonalAccessToken = {
    id: number;
    tokenable: any; // Replace any with the appropriate type
    name: string;
    token: string;
    abilities: string | "";
    last_used_at: string | "";
    expires_at: string | "";
    created_at: string;
    updated_at: string;
};

export type Order = {
    id: number;
    created_at: string;
    updated_at: string;
    name: string;
    menu_id: number; // Assuming the foreign key is a number
    detail: string;
    status: 'pending' | 'ordered' | 'ready' | 'served';
    table_id: number; // Assuming the foreign key is a number
};

export type Category = {
    id: number;
    created_at: string;
    updated_at: string;
    name: string;
};

export type Table = {
    id: number;
    created_at: string;
    updated_at: string;
    status: 'available' | 'used';
    user_id: number; // Assuming the foreign key is a number
};

export type Menu = {
    id: number;
    created_at: string;
    updated_at: string;
    name: string;
    category_id: number; // Assuming the foreign key is a number
    imgPath: string | "";
    description: string;
    status: 'available' | 'outofstock';
    price: number;
};