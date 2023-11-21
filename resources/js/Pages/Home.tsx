import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/inertia-react';
import { Navbar } from '@/components/Navbar';
import { Profile } from '@/components/Profile';
import { Assets } from '@/components/Assets';
import { XIcon } from '@heroicons/react/outline';

export interface Asset {
  assetId: string;
  templateId: string;
  owner: string;
  name: string;
  imgUrl: string;
  stakeFactor: number;
  stakedItem?: StakedItem;
}

export interface StakedItem {
  id: number;
  user_id: number;
  token_id: number;
  item_id: number;
  token: Token;
}

export interface Wallet {
  id: number;
  user_id: number;
  token_id: number;
  balance: number;
  token: Token;
}

export interface Token {
  id: number;
  token_id: string;
  name: string;
  img_url: string;
  stake_power: number;
}

export interface User {
  account_id: string;
}

interface HomeProps {
  assets: Asset[];
  wallets: Wallet[];
}

export default function Home({ assets, wallets }: HomeProps) {
  const [hasErrors, setHasErrors] = useState(false);
  const { errors } = usePage().props;

  useEffect(() => {
    if (Object.keys(errors).length) {
      setHasErrors(true);
    }
  }, [errors]);

  return (
    <>
      <div
        className="fixed bg-cover inset-0 z-0 bg-no-repeat bg-center"
        style={{
          backgroundImage: 'url("/assets/bg.png")',
        }}
      />
      {hasErrors && (
        <div className="absolute inset-x-0 mt-10 px-32 z-40">
          <div className="bg-gray-900 border border-red-500 text-red-500 rounded-lg p-6">
            <div className="flex justify-between items-center">
              <span>{errors[Object.keys(errors)[0]]}</span>
              <button
                className="bg-gray-800 text-red-500 rounded-full p-2"
                onClick={() => setHasErrors(false)}
              >
                <XIcon className="h-6 w-6" />
              </button>
            </div>
          </div>
        </div>
      )}
      <div className="relative z-10 font-jag max-w-7xl mx-auto">
        <Navbar />

        <div className="flex justify-center mx-auto">
          <img
            className="h-24"
            src="/assets/logo.png"
            alt="Jewels & Gods logo"
          />
        </div>

        <div className="flex justify-between flex-wrap px-10 mt-24 text-teal-500 space-y-4">
          <div className="w-full px-10 md:w-1/2">
            <Profile />

            <div className="mt-4 bg-black bg-opacity-50 backdrop-blur-sm rounded-xl p-4 text-4xl text-center">
              <h2>Staking Altar</h2>
            </div>

            <Assets className="mt-4" assets={assets} wallets={wallets} />
          </div>

          <div className="w-full px-10 md:w-1/2">
            <div className="bg-black bg-opacity-50 backdrop-blur-sm rounded-xl p-4 text-center text-2xl md:text-4xl">
              <h2>Your Vault</h2>
            </div>

            <div className="mt-4">
              <div className="flex items-center space-x-4 bg-black bg-opacity-50 backdrop-blur-sm rounded-xl mt-2 overflow-hidden">
                <img
                  className="h-16"
                  src="/assets/tokens/jag-coin.png"
                  alt="Jewels & Gods"
                />
                <span className="text-xl text-teal-800 md:text-2xl lg:text-4xl">
                  Coming soon
                </span>
              </div>
              {wallets.map(wallet => (
                <div
                  key={wallet.id}
                  className="flex items-center space-x-4 bg-black bg-opacity-50 backdrop-blur-sm rounded-xl mt-2 overflow-hidden"
                >
                  <img
                    className="h-16"
                    src={wallet.token.img_url}
                    alt={wallet.token.name}
                  />
                  <span className="text-xl md:text-2xl lg:text-4xl">
                    {wallet.token.token_id}
                  </span>
                  <span className="text-xl md:text-2xl lg:text-4xl">
                    {wallet.balance}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
