import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { HomePage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Home" />
      <HomePage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
