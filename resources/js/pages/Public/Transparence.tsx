import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { TransparencyPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Transparence" />
      <TransparencyPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
