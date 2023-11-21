import React from 'react';

import * as waxjs from '@waxio/waxjs/dist';
import { Inertia } from '@inertiajs/inertia';

export default function Welcome() {
  const signin = async () => {
    const wax = new waxjs.WaxJS({ rpcEndpoint: 'https://wax.greymass.com' });
    const isAutoLoginAvailable = await wax.isAutoLoginAvailable();

    if (!isAutoLoginAvailable) {
      await wax.login();
    }

    if (wax.user) {
      Inertia.post('/signin', {
        account_id: wax.userAccount,
        key_1: wax.user?.keys[0],
        key_2: wax.user?.keys[1],
      });
    }
  };

  return (
    <>
      <div
        className="fixed bg-cover inset-0 z-0 bg-no-repeat bg-center"
        style={{
          backgroundImage: 'url("/assets/bg.png")',
        }}
      />
      <div className="relative z-10 font-jag max-w-7xl mx-auto h-screen flex items-center">
        <div className="flex-col justify-center mx-auto space-y-10">
          <img
            className="h-24"
            src="/assets/logo.png"
            alt="Jewels & Gods logo"
          />

          <button
            className="border-2 border-teal-500 text-teal-500 text-xl px-4 py-4 w-full max-w-sm rounded-full"
            onClick={signin}
          >
            Login
          </button>
        </div>
      </div>
    </>
  );
}
