import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { ContactPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Contact" />
      <ContactPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
