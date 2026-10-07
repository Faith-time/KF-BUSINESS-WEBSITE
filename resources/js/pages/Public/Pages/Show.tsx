import type { ReactNode } from 'react';
import { Head, usePage } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';

export default function Page() {
  const { props } = usePage();
  return (
    <>
      <Head title="Public/Pages/Show" />
      <div className="max-w-7xl mx-auto p-6">
        <h1 className="font-display text-xl font-bold text-slate-900">Public/Pages/Show</h1>
        <pre className="mt-4 p-4 bg-white border border-slate-200 rounded-xl text-xs overflow-auto">
          {JSON.stringify(props, null, 2)}
        </pre>
      </div>
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
