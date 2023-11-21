import React, { useState } from 'react';
import Carousel from 'react-multi-carousel';
import { Inertia } from '@inertiajs/inertia';
import { Asset, Wallet } from '@/Pages/Home';
import 'react-multi-carousel/lib/styles.css';
import { AssetStake } from './AssetStake';

interface AssetsProps {
  assets: Asset[];
  wallets: Wallet[];
  className?: string;
}

const responsive = {
  superLargeDesktop: {
    // the naming can be any, depends on you.
    breakpoint: { max: 4000, min: 3000 },
    items: 5,
  },
  desktop: {
    breakpoint: { max: 3000, min: 1024 },
    items: 3,
  },
  tablet: {
    breakpoint: { max: 1024, min: 464 },
    items: 2,
  },
  mobile: {
    breakpoint: { max: 464, min: 0 },
    items: 1,
  },
};

export function Assets({ assets, wallets, className }: AssetsProps) {
  const [asset, setAsset] = useState<Asset>();
  const [open, setOpen] = useState(false);

  const stake = (selectedAsset: Asset) => {
    setAsset(selectedAsset);
    setOpen(true);
  };

  const unstake = (selectedAsset: Asset) => {
    Inertia.post(`/assets/${selectedAsset.assetId}/unstake`);
  };

  const claimAll = () => {
    Inertia.post('/claim-all');
  };

  const onClose = () => {
    setOpen(false);
    setAsset(undefined);
  };

  return (
    <>
      <Carousel containerClass={className} responsive={responsive}>
        {assets.map(item => (
          <div
            key={item.assetId}
            className="mr-1 p-2 bg-black bg-opacity-50 backdrop-blur-sm rounded-lg"
          >
            <img
              className="h-32 mx-auto rounded-lg"
              src={item.imgUrl}
              alt={item.name}
            />
            <div className="h-12">
              <h4 className="text-center mt-2">{item.name}</h4>
            </div>

            <div className="flex justify-center">
              {item.stakedItem ? (
                <button
                  onClick={() => unstake(item)}
                  className="mt-2 px-2 py-1 border border-red-400 text-red-400 rounded-full"
                >
                  Unstake
                </button>
              ) : (
                <button
                  onClick={() => stake(item)}
                  className="mt-2 px-2 py-1 border border-teal-500 rounded-full"
                >
                  Stake
                </button>
              )}
            </div>
          </div>
        ))}
      </Carousel>

      <div className="flex justify-center">
        <button
          className="mt-4 bg-yellow-600 text-white text-xl px-4 py-4 w-full max-w-sm mx-auto rounded-full hover:bg-yellow-700"
          onClick={claimAll}
        >
          Claim all
        </button>
      </div>

      <AssetStake
        asset={asset}
        wallets={wallets}
        open={open}
        onClose={onClose}
      />
    </>
  );
}
