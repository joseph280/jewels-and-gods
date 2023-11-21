import { usePage } from '@inertiajs/inertia-react';
import React from 'react';
import { User } from '@/Pages/Home';

export function Profile() {
  const { user } = usePage().props;

  return (
    <div className="flex items-center justify-center flex-wrap space-y-4 sm:justify-start sm:space-y-0 sm:space-x-4 md:justify-center md:space-x-0 md:space-y-4 xl:justify-start xl:space-y-0 xl:space-x-4">
      <img
        className="h-24 w-24 rounded-full border border-4 border-teal-500 md:h-48 md:w-48"
        src="/assets/profile.png"
        alt="Jewels & Gods logo"
      />

      <div className="bg-black bg-opacity-50 backdrop-blur-sm rounded-xl p-4 text-lg sm:text-2xl">
        <p>{(user as User).account_id}</p>
        <p>Young Craftman</p>
      </div>
    </div>
  );
}
