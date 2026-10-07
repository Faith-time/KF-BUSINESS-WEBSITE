import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { AboutPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Apropos" />
      <AboutPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
