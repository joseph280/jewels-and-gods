import React, { Fragment } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { Inertia } from '@inertiajs/inertia';
import { Asset, Wallet } from '@/Pages/Home';

interface AssetStakeProps {
  open: boolean;
  onClose: () => void;
  asset?: Asset;
  wallets: Wallet[];
}

export function AssetStake({ open, onClose, asset, wallets }: AssetStakeProps) {
  const stake = (tokenId: number) => {
    if (asset) {
      Inertia.post(`/assets/${asset.assetId}/stake`, {
        token_id: tokenId,
        template_id: asset.templateId,
      });
      onClose();
    }
  };

  return (
    <Transition.Root show={open} as={Fragment}>
      <Dialog
        as="div"
        className="fixed z-10 inset-0 overflow-y-auto"
        onClose={onClose}
      >
        <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <Transition.Child
            as={Fragment}
            enter="ease-out duration-300"
            enterFrom="opacity-0"
            enterTo="opacity-100"
            leave="ease-in duration-200"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
          >
            <Dialog.Overlay className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </Transition.Child>

          {/* This element is to trick the browser into centering the modal contents. */}
          <span
            className="hidden sm:inline-block sm:align-middle sm:h-screen"
            aria-hidden="true"
          >
            &#8203;
          </span>
          <Transition.Child
            as={Fragment}
            enter="ease-out duration-300"
            enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enterTo="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
            leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div className="inline-block align-bottom bg-black bg-opacity-50 backdrop-blur-sm text-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6">
              <div className="mt-3 text-center sm:mt-5">
                <Dialog.Title as="h3" className="text-lg leading-6 font-jag">
                  Select the God
                </Dialog.Title>
              </div>
              <div className="flex justify-center items_center space-x-2 mt-5 sm:mt-6">
                {wallets.map(wallet => (
                  <button
                    key={wallet.id}
                    type="button"
                    className="p-2 bg-black bg-opacity-50 backdrop-blur-sm rounded-lg border-2 border-transparent hover:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500 sm:text-sm"
                    onClick={() => stake(wallet.token_id)}
                  >
                    <img
                      className="h-20 mx-auto"
                      src={wallet.token.img_url}
                      alt={wallet.token.name}
                    />
                    <div className="h-12 font-jag">
                      <h4 className="text-center mt-2">{wallet.token.name}</h4>
                    </div>
                    <div className="h-24 sm:h-12 font-jag">
                      <h4 className="text-center mt-2 text-sm sm:text-normal">
                        Stake power
                      </h4>
                      <p className="text-center mt-2">{asset?.stakeFactor}/h</p>
                    </div>
                  </button>
                ))}
              </div>
            </div>
          </Transition.Child>
        </div>
      </Dialog>
    </Transition.Root>
  );
}
