import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-react';
import React from 'react';
import { User } from '@/Pages/Home';

export function Navbar() {
  const { user } = usePage().props;

  const logout = () => {
    Inertia.post('/logout');
  };

  return (
    <div className="w-full flex justify-end items-center text-white p-4 space-x-4">
      <div>{(user as User).account_id}</div>
      <button
        onClick={logout}
        className="px-2 py-1 border border-teal-500 rounded-full"
      >
        Log out
      </button>
    </div>
  );
}
